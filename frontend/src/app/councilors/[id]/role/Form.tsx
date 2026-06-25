'use client'

import { useActionState } from 'react'
import { assignRoleAction } from './actions'
import { SubmitButton } from '@/components/SubmitButton'
import { ErrorAlert } from '@/components/ErrorAlert'
import type { TownHallView } from '@/lib/types'

const ROLES = [
  { value: 'maire', label: 'Maire' },
  { value: 'maire_adjoint', label: 'Maire adjoint' },
  { value: 'conseiller_delegue', label: 'Conseiller délégué' },
  { value: 'conseiller', label: 'Conseiller municipal' },
]

const selectClass =
  'w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500'

export function AssignRoleForm({
  councilorId,
  townHalls,
}: {
  councilorId: string
  townHalls: TownHallView[]
}) {
  const [state, action] = useActionState(assignRoleAction, {})

  return (
    <form action={action} className="space-y-5">
      {state.error && <ErrorAlert message={state.error} />}

      <input type="hidden" name="councilorId" value={councilorId} />

      <div className="space-y-1">
        <label htmlFor="townHallCode" className="block text-sm font-medium">Mairie</label>
        {townHalls.length === 0 ? (
          <p className="text-sm text-amber-600">
            Aucune mairie enregistrée.{' '}
            <a href="/town-halls/new" className="underline">Créer une mairie</a> d'abord.
          </p>
        ) : (
          <select id="townHallCode" name="townHallCode" required className={selectClass}>
            <option value="">— Sélectionner une mairie —</option>
            {townHalls.map((t) => (
              <option key={t.code} value={t.code}>
                {t.name} ({t.code}) — {t.maxCouncilors} conseillers, {t.maxAdjoints} adjoints max
              </option>
            ))}
          </select>
        )}
      </div>

      <div className="space-y-1">
        <label htmlFor="role" className="block text-sm font-medium">Rôle</label>
        <select id="role" name="role" required className={selectClass}>
          <option value="">— Sélectionner un rôle —</option>
          {ROLES.map((r) => (
            <option key={r.value} value={r.value}>{r.label}</option>
          ))}
        </select>
        <p className="text-xs text-neutral-500">
          Un seul Maire est autorisé. Le nombre d'adjoints est limité à 30 % de l'effectif (art. L2122-2 CGCT).
        </p>
      </div>

      <SubmitButton label="Attribuer le rôle" pendingLabel="Enregistrement…" />
    </form>
  )
}
