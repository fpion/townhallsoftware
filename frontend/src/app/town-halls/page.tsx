import type { Metadata } from 'next'
import { listTownHalls } from '@/lib/api'
import type { TownHallView } from '@/lib/types'

export const metadata: Metadata = { title: 'Mairies' }

export default async function TownHallsPage() {
  let townHalls: TownHallView[] = []
  try {
    townHalls = await listTownHalls()
  } catch {
    // backend unavailable — empty list
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold">Mairies</h1>
        <a
          href="/town-halls/new"
          className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
        >
          Ajouter une mairie
        </a>
      </div>

      {townHalls.length === 0 ? (
        <div className="rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-10 text-center">
          <p className="text-neutral-500">Aucune mairie enregistrée.</p>
          <a href="/town-halls/new" className="mt-3 inline-block text-sm text-blue-600 hover:underline">
            Créer la première mairie →
          </a>
        </div>
      ) : (
        <div className="divide-y divide-neutral-200 dark:divide-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-800">
          {townHalls.map((t) => (
            <div key={t.code} className="flex items-start justify-between p-4">
              <div>
                <p className="font-medium">{t.name}</p>
                <p className="text-sm text-neutral-500">
                  {t.street}, {t.postalCode} {t.city}
                </p>
                <p className="mt-1 text-xs text-neutral-400">
                  Code INSEE : {t.code} · Population : {t.population.toLocaleString('fr-FR')} hab.
                </p>
              </div>
              <div className="text-right text-sm shrink-0 ml-6">
                <p className="font-medium">{t.maxCouncilors} conseillers</p>
                <p className="text-neutral-500">{t.maxAdjoints} adjoints max</p>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
