import { SearchX, TriangleAlert, Inbox } from "lucide-react";
import type { ReactNode } from "react";
import { Skeleton } from "@/components/ui/skeleton";
import { Button } from "@/components/ui/button";

export function PageHeader({
  eyebrow,
  title,
  description,
  children,
}: {
  eyebrow?: string;
  title: string;
  description?: string;
  children?: ReactNode;
}) {
  return (
    <section className="border-b border-border bg-surface-muted">
      <div className="container-page flex flex-col gap-4 py-12 md:flex-row md:items-end md:justify-between md:py-16">
        <div className="max-w-2xl">
          {eyebrow && (
            <span className="text-xs font-semibold uppercase tracking-[0.14em] text-brand">
              {eyebrow}
            </span>
          )}
          <h1 className="mt-2 text-3xl font-bold md:text-4xl">{title}</h1>
          {description && (
            <p className="mt-3 text-sm text-muted-foreground md:text-base">{description}</p>
          )}
        </div>
        {children}
      </div>
    </section>
  );
}

export function EmptyState({
  title = "Nothing here yet",
  description = "Try adjusting your filters or search terms.",
  onReset,
  icon = "search",
}: {
  title?: string;
  description?: string;
  onReset?: () => void;
  icon?: "search" | "inbox";
}) {
  const Icon = icon === "inbox" ? Inbox : SearchX;
  return (
    <div className="flex flex-col items-center justify-center rounded-xl border border-dashed border-border bg-surface px-6 py-16 text-center">
      <Icon className="size-8 text-muted-foreground" />
      <h3 className="mt-4 text-base font-semibold">{title}</h3>
      <p className="mt-1 max-w-sm text-sm text-muted-foreground">{description}</p>
      {onReset && (
        <Button variant="outline" size="sm" className="mt-5" onClick={onReset}>
          Clear filters
        </Button>
      )}
    </div>
  );
}

export function ErrorState({ onRetry }: { onRetry?: () => void }) {
  return (
    <div className="flex flex-col items-center justify-center rounded-xl border border-error/30 bg-error-soft px-6 py-16 text-center">
      <TriangleAlert className="size-8 text-error" />
      <h3 className="mt-4 text-base font-semibold">We couldn't load this</h3>
      <p className="mt-1 text-sm text-muted-foreground">
        Something went wrong while fetching data.
      </p>
      {onRetry && (
        <Button variant="outline" size="sm" className="mt-5" onClick={onRetry}>
          Try again
        </Button>
      )}
    </div>
  );
}

export function JobCardSkeleton() {
  return (
    <div className="rounded-xl border border-border bg-surface p-5 shadow-card">
      <Skeleton className="h-5 w-2/3" />
      <Skeleton className="mt-3 h-4 w-1/3" />
      <Skeleton className="mt-5 h-3 w-full" />
      <Skeleton className="mt-2 h-3 w-4/5" />
      <div className="mt-5 flex gap-2">
        <Skeleton className="h-6 w-20 rounded-full" />
        <Skeleton className="h-6 w-24 rounded-full" />
        <Skeleton className="h-6 w-16 rounded-full" />
      </div>
    </div>
  );
}

export function JobListSkeleton({ count = 5 }: { count?: number }) {
  return (
    <div className="space-y-4">
      {Array.from({ length: count }).map((_, i) => (
        <JobCardSkeleton key={i} />
      ))}
    </div>
  );
}
