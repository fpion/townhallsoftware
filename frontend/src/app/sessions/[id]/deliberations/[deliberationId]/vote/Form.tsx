'use client'

import { useActionState } from 'react'
import { voteAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'

export function VoteForm({
  sessionId,
  deliberationId,
}: {
  sessionId: string
  deliberationId: string
}) {
  const boundAction = voteAction.bind(null, sessionId, deliberationId)
  const [state, action] = useActionState(boundAction, {})

  return (
    <form action={action} className="space-y-6">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="grid grid-cols-3 gap-4">
        <div className="space-y-1">
          <label htmlFor="pour" className="block text-sm font-medium text-green-700 dark:text-green-400">
            Pour
          </label>
          <input
            id="pour"
            name="pour"
            type="number"
            min="0"
            required
            defaultValue={0}
            className="w-full rounded-lg border border-green-300 dark:border-green-800 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-center outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>

        <div className="space-y-1">
          <label htmlFor="contre" className="block text-sm font-medium text-red-700 dark:text-red-400">
            Contre
          </label>
          <input
            id="contre"
            name="contre"
            type="number"
            min="0"
            required
            defaultValue={0}
            className="w-full rounded-lg border border-red-300 dark:border-red-800 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-center outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>

        <div className="space-y-1">
          <label htmlFor="abstention" className="block text-sm font-medium text-neutral-600 dark:text-neutral-400">
            Abstention
          </label>
          <input
            id="abstention"
            name="abstention"
            type="number"
            min="0"
            required
            defaultValue={0}
            className="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-center outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>

      <SubmitButton label="Enregistrer le vote" pendingLabel="Enregistrement…" />
    </form>
  )
}
