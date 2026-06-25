'use client'

import { useActionState } from 'react'
import { addDeliberationAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'

export function AddDeliberationForm({ sessionId }: { sessionId: string }) {
  const boundAction = addDeliberationAction.bind(null, sessionId)
  const [state, action] = useActionState(boundAction, {})

  return (
    <form action={action} className="space-y-6">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="space-y-1">
        <label htmlFor="title" className="block text-sm font-medium">
          Intitulé de la délibération
        </label>
        <input
          id="title"
          name="title"
          type="text"
          required
          placeholder="ex : Approbation du budget primitif 2026"
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div className="space-y-1">
        <label htmlFor="description" className="block text-sm font-medium">
          Exposé des motifs
        </label>
        <textarea
          id="description"
          name="description"
          required
          rows={5}
          placeholder="Décrivez les éléments de la délibération…"
          className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-y"
        />
      </div>

      <SubmitButton label="Ajouter la délibération" pendingLabel="Ajout…" />
    </form>
  )
}
