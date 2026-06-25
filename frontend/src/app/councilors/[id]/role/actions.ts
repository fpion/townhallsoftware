'use server'

import { redirect } from 'next/navigation'
import { assignCouncilorRole } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function assignRoleAction(
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const councilorId = formData.get('councilorId') as string
  const townHallCode = formData.get('townHallCode') as string
  const role = formData.get('role') as string

  if (!councilorId || !townHallCode || !role) {
    return { error: 'Tous les champs sont obligatoires.' }
  }

  try {
    await assignCouncilorRole(councilorId, { townHallCode, role })
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de l\'attribution du rôle.' }
  }

  redirect('/councilors')
}
