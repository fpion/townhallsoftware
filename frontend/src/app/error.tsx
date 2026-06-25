'use client'

export default function Error({
  error,
  unstable_retry,
}: {
  error: Error & { digest?: string }
  unstable_retry: () => void
}) {
  return (
    <div className="text-center py-20">
      <h2 className="text-2xl font-semibold mb-4 text-red-600">Une erreur est survenue</h2>
      <p className="text-neutral-600 dark:text-neutral-400 mb-8">{error.message}</p>
      <button
        onClick={unstable_retry}
        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
      >
        Réessayer
      </button>
    </div>
  )
}
