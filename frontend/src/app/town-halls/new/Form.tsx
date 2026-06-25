'use client'

import { useActionState } from 'react'
import { createTownHallAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'

function Field({
  id,
  label,
  hint,
  children,
}: {
  id: string
  label: string
  hint?: string
  children: React.ReactNode
}) {
  return (
    <div className="space-y-1">
      <label htmlFor={id} className="block text-sm font-medium">
        {label}
      </label>
      {hint && <p className="text-xs text-neutral-500">{hint}</p>}
      {children}
    </div>
  )
}

const inputClass =
  'w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500'

export function CreateTownHallForm() {
  const [state, action] = useActionState(createTownHallAction, {})

  return (
    <form action={action} className="space-y-5">
      {state.error && <ErrorAlert message={state.error} />}

      <Field id="code" label="Code INSEE" hint="Code à 5 chiffres identifiant la commune.">
        <input id="code" name="code" type="text" required maxLength={10} placeholder="ex : 75056" className={inputClass} />
      </Field>

      <Field id="name" label="Nom de la mairie">
        <input id="name" name="name" type="text" required placeholder="ex : Mairie de Paris" className={inputClass} />
      </Field>

      <Field id="population" label="Population" hint="Détermine le nombre légal de conseillers (art. L2121-2 CGCT).">
        <input id="population" name="population" type="number" required min={0} placeholder="ex : 12500" className={inputClass} />
      </Field>

      <fieldset className="space-y-3">
        <legend className="text-sm font-medium">Adresse</legend>
        <input id="street" name="street" type="text" required placeholder="Rue" className={inputClass} />
        <div className="grid grid-cols-2 gap-3">
          <input id="postalCode" name="postalCode" type="text" required placeholder="Code postal" className={inputClass} />
          <input id="city" name="city" type="text" required placeholder="Ville" className={inputClass} />
        </div>
      </fieldset>

      <SubmitButton label="Créer la mairie" pendingLabel="Création…" />
    </form>
  )
}
