import type { Metadata } from 'next'
import { listTownHalls } from '@/lib/api'
import { AssignRoleForm } from './Form'

export const metadata: Metadata = { title: 'Attribuer un rôle' }

export default async function AssignRolePage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params

  let townHalls = []
  try {
    townHalls = await listTownHalls()
  } catch {
    // backend unavailable
  }

  return (
    <div className="max-w-xl mx-auto">
      <div className="mb-8">
        <a href="/councilors" className="text-sm text-neutral-500 hover:text-foreground">
          ← Retour aux conseillers
        </a>
        <h1 className="mt-3 text-2xl font-bold">Attribuer un rôle</h1>
      </div>
      <AssignRoleForm councilorId={id} townHalls={townHalls} />
    </div>
  )
}
