'use server'

import { redirect } from 'next/navigation'
import { registerAttendance } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function registerAttendanceAction(
  sessionId: string,
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const councilorId = formData.get('councilorId') as string
  const status = formData.get('status') as string
  const proxyHolderId = (formData.get('proxyHolderId') as string) || undefined

  if (!councilorId || !status) {
    return { error: 'Tous les champs obligatoires doivent être remplis.' }
  }

  if (status === 'procuration' && !proxyHolderId) {
    return { error: 'Le porteur de procuration est obligatoire pour ce statut.' }
  }

  try {
    await registerAttendance(sessionId, { councilorId, status, proxyHolderId })
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de l\'enregistrement.' }
  }

  redirect(`/sessions/${sessionId}`)
}
