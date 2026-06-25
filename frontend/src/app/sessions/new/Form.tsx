'use client'

import { useActionState } from 'react'
import { createSessionAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'

export function CreateSessionForm() {
  const [state, action] = useActionState(createSessionAction, {})

  const minDate = new Date()
  minDate.setDate(minDate.getDate() + 6)
  const minDateStr = minDate.toISOString().slice(0, 16)

  return (
    <form action={action} className="space-y-6">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="space-y-1">
        <label htmlFor="townHallCode" className="block text-sm font-medium">
          Code INSEE de la mairie
        </label>
        <input
          id="townHallCode"
          name="townHallCode"
          type="text"
          required
          placeholder="ex : 75056"
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div className="space-y-1">
        <label htmlFor="date" className="block text-sm font-medium">
          Date et heure de la séance
        </label>
        <p className="text-xs text-neutral-500">
          Les convocations doivent être envoyées au moins 5 jours avant (art. L2121-11 CGCT).
        </p>
        <input
          id="date"
          name="date"
          type="datetime-local"
          required
          min={minDateStr}
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div className="space-y-1">
        <label htmlFor="orderOfBusiness" className="block text-sm font-medium">
          Ordre du jour
        </label>
        <textarea
          id="orderOfBusiness"
          name="orderOfBusiness"
          required
          rows={5}
          placeholder="Listez les points à l'ordre du jour…"
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-y"
        />
      </div>

      <SubmitButton label="Créer la séance" pendingLabel="Création…" />
    </form>
  )
}
