'use server'

import { refresh } from 'next/cache'
import {
  sendCouncilSessionInvitations,
  openCouncilSession,
  closeCouncilSession,
} from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function sendInvitationsAction(
  sessionId: string,
  _prev: ActionState,
  _formData: FormData,
): Promise<ActionState> {
  try {
    await sendCouncilSessionInvitations(sessionId)
    refresh()
    return {}
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de l\'envoi des convocations.' }
  }
}

export async function openSessionAction(
  sessionId: string,
  _prev: ActionState,
  _formData: FormData,
): Promise<ActionState> {
  try {
    await openCouncilSession(sessionId)
    refresh()
    return {}
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de l\'ouverture de la séance.' }
  }
}

export async function closeSessionAction(
  sessionId: string,
  _prev: ActionState,
  _formData: FormData,
): Promise<ActionState> {
  try {
    await closeCouncilSession(sessionId)
    refresh()
    return {}
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de la clôture de la séance.' }
  }
}
