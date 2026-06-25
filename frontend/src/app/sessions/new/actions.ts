'use server'

import { redirect } from 'next/navigation'
import { createCouncilSession } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function createSessionAction(
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const townHallCode = formData.get('townHallCode') as string
  const date = formData.get('date') as string
  const exceptional = formData.get('exceptional') === 'true'

  if (!townHallCode || !date) {
    return { error: 'La mairie et la date sont obligatoires.' }
  }

  let id: string
  try {
    const result = await createCouncilSession({ townHallCode, date, exceptional })
    id = result.id
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de la création.' }
  }

  redirect(`/sessions/${id}`)
}
