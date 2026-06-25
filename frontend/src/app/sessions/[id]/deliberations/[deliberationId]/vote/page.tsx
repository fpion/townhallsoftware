import type { Metadata } from 'next'
import Link from 'next/link'
import { notFound } from 'next/navigation'
import { getCouncilSession } from '@/lib/api'
import { VoteForm } from './Form'

export const metadata: Metadata = { title: 'Vote sur une délibération' }

export default async function VotePage({
  params,
}: {
  params: Promise<{ id: string; deliberationId: string }>
}) {
  const { id, deliberationId } = await params

  let session
  try {
    session = await getCouncilSession(id)
  } catch {
    notFound()
  }

  const deliberation = session.deliberations.find((d) => d.id === deliberationId)
  if (!deliberation || deliberation.status !== 'pending') {
    notFound()
  }

  return (
    <div className="max-w-xl mx-auto">
      <div className="mb-8">
        <Link href={`/sessions/${id}`} className="text-sm text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">
          ← Retour à la séance
        </Link>
      </div>

      <div className="mb-8">
        <p className="text-xs text-neutral-500 font-mono mb-1">{deliberation.number}</p>
        <h1 className="text-2xl font-bold">{deliberation.title}</h1>
        <p className="text-sm text-neutral-600 dark:text-neutral-400 mt-2">{deliberation.description}</p>
      </div>

      <VoteForm sessionId={id} deliberationId={deliberationId} />
    </div>
  )
}
