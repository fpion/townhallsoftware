import type { Metadata } from 'next'
import { CreateSessionForm } from './Form'

export const metadata: Metadata = { title: 'Nouvelle séance' }

export default function NewSessionPage() {
  return (
    <div className="max-w-xl mx-auto">
      <h1 className="text-2xl font-bold mb-8">Nouvelle séance du conseil municipal</h1>
      <CreateSessionForm />
    </div>
  )
}
