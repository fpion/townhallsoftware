'use client'

import { useActionState } from 'react'
import { sendInvitationsAction, openSessionAction, closeSessionAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'
import type { SessionStatus } from '@/lib/types'

function ActionForm({
  action,
  label,
  pendingLabel,
  variant = 'primary',
}: {
  action: (prev: { error?: string }, formData: FormData) => Promise<{ error?: string }>
  label: string
  pendingLabel: string
  variant?: 'primary' | 'warning' | 'danger'
}) {
  const [state, formAction] = useActionState(action, {})

  const colors = {
    primary: 'bg-blue-600 text-white hover:bg-blue-700',
    warning: 'bg-yellow-500 text-white hover:bg-yellow-600',
    danger: 'bg-red-600 text-white hover:bg-red-700',
  }

  return (
    <form action={formAction} className="inline-block">
      {state.error && (
        <div className="mb-2">
          <ErrorAlert message={state.error} />
        </div>
      )}
      <SubmitButton label={label} pendingLabel={pendingLabel} className={colors[variant]} />
    </form>
  )
}

export function SessionActions({
  sessionId,
  status,
}: {
  sessionId: string
  status: SessionStatus
}) {
  const sendInvitations = sendInvitationsAction.bind(null, sessionId)
  const openSession = openSessionAction.bind(null, sessionId)
  const closeSession = closeSessionAction.bind(null, sessionId)

  return (
    <div className="flex flex-wrap gap-3">
      {status === 'planned' && (
        <>
          <ActionForm
            action={sendInvitations}
            label="Envoyer les convocations"
            pendingLabel="Envoi…"
            variant="primary"
          />
          <ActionForm
            action={openSession}
            label="Ouvrir la séance"
            pendingLabel="Ouverture…"
            variant="warning"
          />
        </>
      )}
      {status === 'open' && (
        <ActionForm
          action={closeSession}
          label="Clôturer la séance"
          pendingLabel="Clôture…"
          variant="danger"
        />
      )}
    </div>
  )
}
