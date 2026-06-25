'use server'

import { redirect } from 'next/navigation'
import { voteOnDeliberation } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function voteAction(
  sessionId: string,
  deliberationId: string,
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const pour = parseInt(formData.get('pour') as string, 10)
  const contre = parseInt(formData.get('contre') as string, 10)
  const abstention = parseInt(formData.get('abstention') as string, 10)

  if (isNaN(pour) || isNaN(contre) || isNaN(abstention)) {
    return { error: 'Les votes doivent être des nombres entiers.' }
  }

  if (pour < 0 || contre < 0 || abstention < 0) {
    return { error: 'Les votes ne peuvent pas être négatifs.' }
  }

  try {
    await voteOnDeliberation(sessionId, deliberationId, { pour, contre, abstention })
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors du vote.' }
  }

  redirect(`/sessions/${sessionId}`)
}
