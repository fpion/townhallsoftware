import type { Metadata } from 'next'
import { listTownHalls } from '@/lib/api'
import type { TownHallView } from '@/lib/types'
import { CreateSessionForm } from './Form'

export const metadata: Metadata = { title: 'Nouvelle séance' }

export default async function NewSessionPage({
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
      <h1 className="text-2xl font-bold mb-8">Nouvelle séance du conseil municipal</h1>
      <CreateSessionForm townHalls={townHalls} preselectedCode={townHallCode ?? ''} />
    </div>
  )
}
