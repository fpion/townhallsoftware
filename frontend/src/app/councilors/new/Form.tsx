'use client'

import { useActionState } from 'react'
import { createCouncilorAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'
import type { TownHallView } from '@/lib/types'

const inputClass =
  'w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500'

interface Props {
  townHalls: TownHallView[]
  preselectedCode: string
}

export function CreateCouncilorForm({ townHalls, preselectedCode }: Props) {
  const [state, action] = useActionState(createCouncilorAction, {})

  return (
    <form action={action} className="space-y-5">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="space-y-1">
        <label htmlFor="townHallCode" className="block text-sm font-medium">Mairie</label>
        {townHalls.length === 0 ? (
          <p className="text-sm text-red-600">
            Aucune mairie enregistrée.{' '}
            <a href="/town-halls/new" className="underline">Créer une mairie</a> d&apos;abord.
          </p>
        ) : (
          <select
            id="townHallCode"
            name="townHallCode"
            required
            defaultValue={preselectedCode}
            className={inputClass}
          >
            <option value="">— Sélectionner une mairie —</option>
            {townHalls.map((t) => (
              <option key={t.code} value={t.code}>
                {t.name} ({t.code})
              </option>
            ))}
          </select>
        )}
      </div>

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
        Le rôle par défaut est <strong>Conseiller municipal</strong>. Vous pourrez l&apos;ajuster depuis la fiche de la mairie.
      </p>

      <SubmitButton label="Ajouter le conseiller" pendingLabel="Création…" />
    </form>
  )
}
