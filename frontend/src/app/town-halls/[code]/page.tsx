import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { listTownHalls, listCouncilSessions } from '@/lib/api'
import type { CouncilSessionSummaryView } from '@/lib/types'

export const metadata: Metadata = { title: 'Détail mairie' }

const statusColors: Record<string, string> = {
  planned: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
  open:    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
  closed:  'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400',
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
  try {
    sessions = await listCouncilSessions(code)
  } catch {
    // backend unavailable
  }

  return (
    <div className="space-y-8">
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
        <a
          href={`/sessions/new?townHallCode=${encodeURIComponent(code)}`}
          className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors shrink-0"
        >
          Nouvelle séance
        </a>
      </div>

      {/* Liste des séances */}
      <div className="space-y-4">
        <h2 className="text-lg font-semibold">Séances du conseil municipal</h2>

        {sessions.length === 0 ? (
          <div className="rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-10 text-center">
            <p className="text-neutral-500">Aucune séance planifiée.</p>
            <a
              href={`/sessions/new?townHallCode=${encodeURIComponent(code)}`}
              className="mt-3 inline-block text-sm text-blue-600 hover:underline"
            >
              Créer la première séance →
            </a>
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
                <span
                  className={`rounded-full px-3 py-1 text-xs font-medium ${statusColors[s.status] ?? ''}`}
                >
                  {s.statusLabel}
                </span>
              </a>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}
