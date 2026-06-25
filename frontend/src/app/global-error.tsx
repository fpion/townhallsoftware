'use client'

export default function GlobalError({
  unstable_retry,
}: {
  error: Error & { digest?: string }
  unstable_retry: () => void
}) {
  return (
    <html lang="fr">
      <body className="flex items-center justify-center min-h-screen bg-white dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100">
        <div className="text-center p-8">
          <h2 className="text-2xl font-semibold mb-4 text-red-600">Erreur critique</h2>
          <p className="text-neutral-600 dark:text-neutral-400 mb-8">
            Une erreur inattendue s&apos;est produite.
          </p>
          <button
            onClick={unstable_retry}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            Réessayer
          </button>
        </div>
      </body>
    </html>
  )
}
