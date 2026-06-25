import type { Metadata } from 'next'
import { listCouncilors } from '@/lib/api'
import type { CouncilorView } from '@/lib/types'

export const metadata: Metadata = { title: 'Conseillers municipaux' }

const roleColors: Record<string, string> = {
  maire: 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
  maire_adjoint: 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
  conseiller_delegue: 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
  conseiller: 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
}

export default async function CouncilorsPage() {
  let councilors: CouncilorView[] = []
  try {
    councilors = await listCouncilors()
  } catch {
    // backend unavailable — empty list
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold">Conseillers municipaux</h1>
        <a
          href="/councilors/new"
          className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
        >
          Ajouter un conseiller
        </a>
      </div>

      {councilors.length === 0 ? (
        <div className="rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-10 text-center">
          <p className="text-neutral-500">Aucun conseiller enregistré.</p>
          <a href="/councilors/new" className="mt-3 inline-block text-sm text-blue-600 hover:underline">
            Ajouter le premier conseiller →
          </a>
        </div>
      ) : (
        <div className="divide-y divide-neutral-200 dark:divide-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-800">
          {councilors.map((c) => (
            <div key={c.id} className="flex items-center justify-between p-4">
              <div>
                <p className="font-medium">
                  {c.firstName} {c.lastName}
                </p>
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
    </div>
  )
}
