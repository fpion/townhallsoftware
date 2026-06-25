'use client'

import { useActionState, useState } from 'react'
import { createSessionAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'
import type { TownHallView } from '@/lib/types'

const inputClass =
  'w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500'

function minDateFor(daysAhead: number): string {
  const d = new Date()
  d.setDate(d.getDate() + daysAhead)
  return d.toISOString().slice(0, 16)
}

interface Props {
  townHalls: TownHallView[]
  preselectedCode: string
}

export function CreateSessionForm({ townHalls, preselectedCode }: Props) {
  const [state, action] = useActionState(createSessionAction, {})
  const [exceptional, setExceptional] = useState(false)

  const noticeDays = exceptional ? 7 : 15
  const minDateStr = minDateFor(noticeDays)

  return (
    <form action={action} className="space-y-6">
      {state.error && <ErrorAlert message={state.error} />}

      <div className="space-y-1">
        <label htmlFor="townHallCode" className="block text-sm font-medium">
          Mairie
        </label>
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

      <div className="rounded-lg border border-neutral-200 dark:border-neutral-800 p-4 space-y-1">
        <label className="flex items-center gap-3 cursor-pointer">
          <input
            type="checkbox"
            name="exceptional"
            value="true"
            checked={exceptional}
            onChange={(e) => setExceptional(e.target.checked)}
            className="h-4 w-4 rounded border-neutral-300 text-blue-600 focus:ring-blue-500"
          />
          <span className="text-sm font-medium">Conseil exceptionnel</span>
        </label>
        <p className="text-xs text-neutral-500 pl-7">
          {exceptional
            ? 'Délai de convocation réduit à 7 jours (art. L2121-11 CGCT).'
            : 'Délai de convocation ordinaire : 15 jours (art. L2121-11 CGCT).'}
        </p>
      </div>

      <div className="space-y-1">
        <label htmlFor="date" className="block text-sm font-medium">
          Date et heure de la séance
        </label>
        <p className="text-xs text-neutral-500">
          Les convocations devront être envoyées au moins {noticeDays} jours avant.
        </p>
        <input
          id="date"
          name="date"
          type="datetime-local"
          required
          min={minDateStr}
          className={inputClass}
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
          className={`${inputClass} resize-y`}
        />
      </div>

      <SubmitButton label="Créer la séance" pendingLabel="Création…" />
    </form>
  )
}
