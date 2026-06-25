'use client'

import { useActionState } from 'react'
import { createCouncilorAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'

const inputClass =
  'w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500'

export function CreateCouncilorForm() {
  const [state, action] = useActionState(createCouncilorAction, {})

  return (
    <form action={action} className="space-y-5">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="space-y-1">
        <label htmlFor="firstName" className="block text-sm font-medium">Prénom</label>
        <input id="firstName" name="firstName" type="text" required className={inputClass} />
      </div>

      <div className="space-y-1">
        <label htmlFor="lastName" className="block text-sm font-medium">Nom</label>
        <input id="lastName" name="lastName" type="text" required className={inputClass} />
      </div>

      <div className="space-y-1">
        <label htmlFor="email" className="block text-sm font-medium">Adresse e-mail</label>
        <input id="email" name="email" type="email" required className={inputClass} />
      </div>

      <p className="text-xs text-neutral-500">
        Le rôle par défaut est <strong>Conseiller municipal</strong>. Vous pourrez l'ajuster depuis la liste des conseillers.
      </p>

      <SubmitButton label="Ajouter le conseiller" pendingLabel="Création…" />
    </form>
  )
}
