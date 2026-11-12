import { ChevronLeft, ChevronRight } from "lucide-react";
import { Button } from "@/components/ui/button";
import type { Pagination } from "@/types";
import { cn } from "@/lib/utils";

export function PaginationBar({
  pagination,
  onPage,
}: {
  pagination: Pagination;
  onPage: (page: number) => void;
}) {
  const { page, totalPages, total, perPage } = pagination;
  if (totalPages <= 1) return null;
  const pages = Array.from({ length: totalPages }, (_, i) => i + 1).filter(
    (p) => p === 1 || p === totalPages || Math.abs(p - page) <= 1,
  );

  return (
    <div className="flex flex-col items-center justify-between gap-4 sm:flex-row">
      <p className="text-sm text-muted-foreground">
        Showing {(page - 1) * perPage + 1}–{Math.min(page * perPage, total)} of {total} jobs
      </p>
      <div className="flex items-center gap-1">
        <Button
          variant="outline"
          size="icon"
          disabled={page === 1}
          onClick={() => onPage(page - 1)}
          aria-label="Previous page"
        >
          <ChevronLeft className="size-4" />
        </Button>
        {pages.map((p, i) => (
          <span key={p} className="flex items-center">
            {i > 0 && p - pages[i - 1] > 1 && (
              <span className="px-1 text-sm text-muted-foreground">…</span>
            )}
            <Button
              variant={p === page ? "default" : "outline"}
              size="icon"
              className={cn("size-9")}
              onClick={() => onPage(p)}
            >
              {p}
            </Button>
          </span>
        ))}
        <Button
          variant="outline"
          size="icon"
          disabled={page === totalPages}
          onClick={() => onPage(page + 1)}
          aria-label="Next page"
        >
          <ChevronRight className="size-4" />
        </Button>
      </div>
    </div>
  );
}
