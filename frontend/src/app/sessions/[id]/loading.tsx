export default function Loading() {
  return (
    <div className="animate-pulse space-y-6">
      <div className="h-8 bg-neutral-200 dark:bg-neutral-800 rounded w-2/3" />
      <div className="h-4 bg-neutral-200 dark:bg-neutral-800 rounded w-1/3" />
      <div className="space-y-3">
        <div className="h-4 bg-neutral-200 dark:bg-neutral-800 rounded" />
        <div className="h-4 bg-neutral-200 dark:bg-neutral-800 rounded w-5/6" />
      </div>
    </div>
  )
}
