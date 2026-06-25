import type { Metadata } from 'next'
import Link from 'next/link'
import { AddDeliberationForm } from './Form'

export const metadata: Metadata = { title: 'Ajouter une délibération' }

export default async function NewDeliberationPage({
  params,
}: {
  params: Promise<{ id: string }>
}) {
  const { id } = await params

  return (
    <div className="max-w-xl mx-auto">
      <div className="mb-8">
        <Link href={`/sessions/${id}`} className="text-sm text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">
          ← Retour à la séance
        </Link>
      </div>
      <h1 className="text-2xl font-bold mb-8">Ajouter une délibération</h1>
      <AddDeliberationForm sessionId={id} />
    </div>
  )
}
