import type { Metadata } from 'next'
import { CreateCouncilorForm } from './Form'

export const metadata: Metadata = { title: 'Nouveau conseiller' }

export default function NewCouncilorPage() {
  return (
    <div className="max-w-xl mx-auto">
      <h1 className="text-2xl font-bold mb-8">Ajouter un conseiller municipal</h1>
      <CreateCouncilorForm />
    </div>
  )
}
