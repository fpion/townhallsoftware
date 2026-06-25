'use server'

import { redirect } from 'next/navigation'
import { addDeliberation } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function addDeliberationAction(
  sessionId: string,
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const title = formData.get('title') as string
  const description = formData.get('description') as string

  if (!title || !description) {
    return { error: 'Le titre et la description sont obligatoires.' }
  }

  try {
    await addDeliberation(sessionId, { title, description })
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de l\'ajout.' }
  }

  redirect(`/sessions/${sessionId}`)
}
