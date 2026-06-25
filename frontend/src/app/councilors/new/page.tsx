import type { Metadata } from 'next'
import { listTownHalls } from '@/lib/api'
import type { TownHallView } from '@/lib/types'
import { CreateCouncilorForm } from './Form'

export const metadata: Metadata = { title: 'Nouveau conseiller' }

export default async function NewCouncilorPage({
  searchParams,
}: {
  searchParams: Promise<{ townHallCode?: string }>
}) {
  const { townHallCode } = await searchParams

  let townHalls: TownHallView[] = []
  try {
    townHalls = await listTownHalls()
  } catch {
    // backend unavailable
  }

  return (
    <div className="max-w-xl mx-auto">
      <h1 className="text-2xl font-bold mb-8">Ajouter un conseiller municipal</h1>
      <CreateCouncilorForm townHalls={townHalls} preselectedCode={townHallCode ?? ''} />
    </div>
  )
}
