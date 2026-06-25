'use server'

import { redirect } from 'next/navigation'
import { createCouncilor } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function createCouncilorAction(
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const townHallCode = formData.get('townHallCode') as string
  const firstName = formData.get('firstName') as string
  const lastName = formData.get('lastName') as string
  const email = formData.get('email') as string

  if (!townHallCode || !firstName || !lastName || !email) {
    return { error: 'Tous les champs sont obligatoires.' }
  }

  try {
    await createCouncilor({ firstName, lastName, email, townHallCode })
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de la création.' }
  }

  redirect(`/town-halls/${townHallCode}`)
}
