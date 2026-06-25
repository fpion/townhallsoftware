'use client'

import { useActionState } from 'react'
import { registerAttendanceAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'

const STATUSES = [
  { value: 'present', label: 'Présent' },
  { value: 'absent_excuse', label: 'Absent excusé' },
  { value: 'absent', label: 'Absent' },
  { value: 'procuration', label: 'Procuration' },
]

export function RegisterAttendanceForm({ sessionId }: { sessionId: string }) {
  const boundAction = registerAttendanceAction.bind(null, sessionId)
  const [state, action, pending] = useActionState(boundAction, {})

  return (
    <form action={action} className="space-y-6">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="space-y-1">
        <label htmlFor="councilorId" className="block text-sm font-medium">
          Identifiant du conseiller (UUID)
        </label>
        <input
          id="councilorId"
          name="councilorId"
          type="text"
          required
          placeholder="ex : 550e8400-e29b-41d4-a716-446655440000"
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm font-mono outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div className="space-y-1">
        <label htmlFor="status" className="block text-sm font-medium">
          Statut de présence
        </label>
        <select
          id="status"
          name="status"
          required
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Sélectionner…</option>
          {STATUSES.map((s) => (
            <option key={s.value} value={s.value}>{s.label}</option>
          ))}
        </select>
      </div>

      <div className="space-y-1">
        <label htmlFor="proxyHolderId" className="block text-sm font-medium">
          Porteur de procuration
          <span className="ml-1 text-xs text-neutral-400">(requis si statut = Procuration)</span>
        </label>
        <input
          id="proxyHolderId"
          name="proxyHolderId"
          type="text"
          placeholder="UUID du conseiller présent portant la procuration"
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm font-mono outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <SubmitButton
        label="Enregistrer la présence"
        pendingLabel="Enregistrement…"
      />
    </form>
  )
}
