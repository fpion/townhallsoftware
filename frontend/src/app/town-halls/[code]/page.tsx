import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { listTownHalls, listCouncilSessions, listCouncilors } from '@/lib/api'
import type { CouncilSessionSummaryView, CouncilorView } from '@/lib/types'

export const metadata: Metadata = { title: 'Détail mairie' }

const statusColors: Record<string, string> = {
  planned: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
  open:    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
  closed:  'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400',
}

const roleColors: Record<string, string> = {
  maire:              'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
  maire_adjoint:      'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
  conseiller_delegue: 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
  conseiller:         'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
}

export default async function TownHallDetailPage({
  params,
}: {
  params: Promise<{ code: string }>
}) {
  const { code } = await params

  let townHall
  try {
    const all = await listTownHalls()
    townHall = all.find((t) => t.code === code)
  } catch {
    townHall = undefined
  }

  if (!townHall) notFound()

  let sessions: CouncilSessionSummaryView[] = []
  let councilors: CouncilorView[] = []

  await Promise.allSettled([
    listCouncilSessions(code).then((s) => { sessions = s }),
    listCouncilors(code).then((c) => { councilors = c }),
  ])

  return (
    <div className="space-y-10">
      {/* En-tête mairie */}
      <div className="flex items-start justify-between">
        <div>
          <p className="text-sm text-neutral-500 mb-1">
            <a href="/town-halls" className="hover:underline">Mairies</a>
            {' / '}
            <span>{townHall.name}</span>
          </p>
          <h1 className="text-2xl font-bold">{townHall.name}</h1>
          <p className="text-neutral-500 mt-1">
            {townHall.street}, {townHall.postalCode} {townHall.city}
          </p>
          <p className="text-sm text-neutral-400 mt-1">
            Code INSEE : {townHall.code} · {townHall.population.toLocaleString('fr-FR')} hab.
            · {townHall.maxCouncilors} conseillers · {townHall.maxAdjoints} adjoints max
          </p>
        </div>
      </div>

      {/* Conseillers */}
      <section className="space-y-4">
        <div className="flex items-center justify-between">
          <h2 className="text-lg font-semibold">
            Conseil municipal
            <span className="ml-2 text-sm font-normal text-neutral-500">
              ({councilors.length} / {townHall.maxCouncilors})
            </span>
          </h2>
          <a
            href={`/councilors/new?townHallCode=${encodeURIComponent(code)}`}
            className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors shrink-0"
          >
            Ajouter un conseiller
          </a>
        </div>

        {councilors.length === 0 ? (
          <div className="rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-8 text-center">
            <p className="text-neutral-500">Aucun conseiller enregistré pour cette mairie.</p>
          </div>
        ) : (
          <div className="divide-y divide-neutral-200 dark:divide-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-800">
            {councilors.map((c) => (
              <div key={c.id} className="flex items-center justify-between p-4">
                <div>
                  <p className="font-medium">{c.firstName} {c.lastName}</p>
                  <p className="text-sm text-neutral-500">{c.email}</p>
                </div>
                <div className="flex items-center gap-3 shrink-0 ml-6">
                  <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium ${roleColors[c.role] ?? roleColors.conseiller}`}>
                    {c.roleLabel}
                  </span>
                  <a
                    href={`/councilors/${c.id}/role`}
                    className="text-sm text-blue-600 hover:underline"
                  >
                    Modifier le rôle
                  </a>
                </div>
              </div>
            ))}
          </div>
        )}
      </section>

      {/* Séances */}
      <section className="space-y-4">
        <div className="flex items-center justify-between">
          <h2 className="text-lg font-semibold">Séances du conseil municipal</h2>
          <a
            href={`/sessions/new?townHallCode=${encodeURIComponent(code)}`}
            className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors shrink-0"
          >
            Nouvelle séance
          </a>
        </div>

        {sessions.length === 0 ? (
          <div className="rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-8 text-center">
            <p className="text-neutral-500">Aucune séance planifiée.</p>
          </div>
        ) : (
          <div className="divide-y divide-neutral-200 dark:divide-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-800">
            {sessions.map((s) => (
              <a
                key={s.id}
                href={`/sessions/${s.id}`}
                className="flex items-center justify-between p-4 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors"
              >
                <div>
                  <p className="font-medium">
                    {new Date(s.date).toLocaleDateString('fr-FR', {
                      weekday: 'long',
                      day: 'numeric',
                      month: 'long',
                      year: 'numeric',
                    })}
                    {' à '}
                    {new Date(s.date).toLocaleTimeString('fr-FR', {
                      hour: '2-digit',
                      minute: '2-digit',
                    })}
                  </p>
                  {s.exceptional && (
                    <p className="text-xs text-orange-600 dark:text-orange-400 mt-0.5">
                      Séance exceptionnelle
                    </p>
                  )}
                </div>
                <span className={`rounded-full px-3 py-1 text-xs font-medium ${statusColors[s.status] ?? ''}`}>
                  {s.statusLabel}
                </span>
              </a>
            ))}
          </div>
        )}
      </section>
    </div>
  )
}
