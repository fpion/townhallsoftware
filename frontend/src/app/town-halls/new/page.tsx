import type { Metadata } from 'next'
import { CreateTownHallForm } from './Form'

export const metadata: Metadata = { title: 'Nouvelle mairie' }

export default function NewTownHallPage() {
  return (
    <div className="max-w-xl mx-auto">
      <h1 className="text-2xl font-bold mb-8">Ajouter une mairie</h1>
      <CreateTownHallForm />
    </div>
  )
}
