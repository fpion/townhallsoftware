import type { Metadata } from 'next'
import Link from 'next/link'
import { notFound } from 'next/navigation'
import { getCouncilSession } from '@/lib/api'
import { SessionStatusBadge, DeliberationStatusBadge, AttendanceStatusBadge } from '@/components/StatusBadge'
import { SessionActions } from './SessionActions'

export async function generateMetadata({
  params,
}: {
  params: Promise<{ id: string }>
}): Promise<Metadata> {
  const { id } = await params
  return { title: `Séance ${id.slice(0, 8)}…` }
}

export default async function SessionPage({
  params,
}: {
  params: Promise<{ id: string }>
}) {
  const { id } = await params

  let session
  try {
    session = await getCouncilSession(id)
  } catch (e) {
    if (e instanceof Error && e.message.includes('introuvable')) {
      notFound()
    }
    throw e
  }

  const formattedDate = new Date(session.date).toLocaleString('fr-FR', {
    dateStyle: 'full',
    timeStyle: 'short',
  })

  return (
    <div className="space-y-10">
      {/* En-tête */}
      <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <div className="flex items-center gap-3 mb-1">
            <h1 className="text-2xl font-bold">Conseil Municipal</h1>
            <SessionStatusBadge status={session.status} label={session.statusLabel} />
          </div>
          <p className="text-sm text-neutral-500">
            {session.townHallCode} · {formattedDate}
          </p>
        </div>
        <SessionActions sessionId={id} status={session.status} />
      </div>

      {/* Ordre du jour */}
      <section>
        <h2 className="text-lg font-semibold mb-2">Ordre du jour</h2>
        <p className="whitespace-pre-wrap text-sm text-neutral-700 dark:text-neutral-300 bg-neutral-50 dark:bg-neutral-900 rounded-lg p-4 border border-neutral-200 dark:border-neutral-800">
          {session.orderOfBusiness}
        </p>
      </section>

      {/* Présences */}
      <section>
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-lg font-semibold">
            Présences
            <span className="ml-2 text-sm font-normal text-neutral-500">
              ({session.presentCount} présent{session.presentCount > 1 ? 's' : ''})
            </span>
          </h2>
          {session.status !== 'closed' && (
            <Link
              href={`/sessions/${id}/attendances/new`}
              className="text-sm text-blue-600 hover:underline"
            >
              + Enregistrer une présence
            </Link>
          )}
        </div>

        {session.attendances.length === 0 ? (
          <p className="text-sm text-neutral-500 italic">Aucune présence enregistrée.</p>
        ) : (
          <ul className="divide-y divide-neutral-200 dark:divide-neutral-800 border border-neutral-200 dark:border-neutral-800 rounded-lg overflow-hidden">
            {session.attendances.map((a) => (
              <li key={a.councilorId} className="flex items-center justify-between px-4 py-3 bg-white dark:bg-neutral-900">
                <span className="text-sm font-mono text-neutral-500">{a.councilorId}</span>
                <div className="flex items-center gap-2">
                  {a.proxyHolderId && (
                    <span className="text-xs text-neutral-400">
                      → {a.proxyHolderId.slice(0, 8)}…
                    </span>
                  )}
                  <AttendanceStatusBadge status={a.status} label={a.statusLabel} />
                </div>
              </li>
            ))}
          </ul>
        )}
      </section>

      {/* Délibérations */}
      <section>
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-lg font-semibold">Délibérations</h2>
          {session.status === 'open' && (
            <Link
              href={`/sessions/${id}/deliberations/new`}
              className="text-sm text-blue-600 hover:underline"
            >
              + Ajouter une délibération
            </Link>
          )}
        </div>

        {session.deliberations.length === 0 ? (
          <p className="text-sm text-neutral-500 italic">Aucune délibération enregistrée.</p>
        ) : (
          <ul className="space-y-4">
            {session.deliberations.map((d) => (
              <li
                key={d.id}
                className="rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4"
              >
                <div className="flex items-start justify-between gap-4 mb-2">
                  <div>
                    <span className="text-xs text-neutral-500 font-mono">{d.number}</span>
                    <h3 className="font-medium">{d.title}</h3>
                  </div>
                  <DeliberationStatusBadge status={d.status} label={d.statusLabel} />
                </div>
                <p className="text-sm text-neutral-600 dark:text-neutral-400 mb-3">{d.description}</p>

                {d.vote ? (
                  <div className="flex gap-4 text-sm">
                    <span className="text-green-600 font-medium">Pour : {d.vote.pour}</span>
                    <span className="text-red-600 font-medium">Contre : {d.vote.contre}</span>
                    <span className="text-neutral-500">Abstention : {d.vote.abstention}</span>
                  </div>
                ) : (
                  session.status === 'open' && d.status === 'pending' && (
                    <Link
                      href={`/sessions/${id}/deliberations/${d.id}/vote`}
                      className="text-sm text-blue-600 hover:underline"
                    >
                      Procéder au vote →
                    </Link>
                  )
                )}
              </li>
            ))}
          </ul>
        )}
      </section>
    </div>
  )
}
