import { Link } from "@tanstack/react-router";
import { MapPin, Clock, Briefcase, Building2, CalendarDays } from "lucide-react";
import type { Job } from "@/types";
import { relativeDate } from "@/lib/format";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

export function JobMeta({ job, className }: { job: Job; className?: string }) {
  const items = [
    { icon: Building2, label: job.department },
    { icon: MapPin, label: `${job.location} · ${job.workMode}` },
    { icon: Briefcase, label: job.jobType },
    { icon: Clock, label: job.experience },
    { icon: CalendarDays, label: relativeDate(job.postedDate) },
  ];
  return (
    <ul className={cn("flex flex-wrap gap-x-5 gap-y-2 text-sm text-muted-foreground", className)}>
      {items.map((i) => (
        <li key={i.label} className="flex items-center gap-1.5">
          <i.icon className="size-4 shrink-0" />
          <span>{i.label}</span>
        </li>
      ))}
    </ul>
  );
}

export function JobCard({ job }: { job: Job }) {
  return (
    <article className="group rounded-xl border border-border bg-surface p-5 shadow-card transition-all hover:-translate-y-0.5 hover:shadow-lift md:p-6">
      <div className="flex flex-wrap items-start justify-between gap-3">
        <div>
          <div className="flex flex-wrap items-center gap-2">
            <h3 className="font-display text-lg font-semibold leading-tight">
              <Link
                to="/jobs/$slug"
                params={{ slug: job.slug }}
                className="transition-colors hover:text-brand"
              >
                {job.title}
              </Link>
            </h3>
            {job.featured && (
              <Badge className="bg-brand-soft text-accent-foreground hover:bg-brand-soft">
                Featured
              </Badge>
            )}
          </div>
          <p className="mt-1 text-xs text-muted-foreground">
            {job.jobId} · {job.category}
          </p>
        </div>
        <Button asChild size="sm" variant="outline">
          <Link to="/jobs/$slug" params={{ slug: job.slug }}>
            View role
          </Link>
        </Button>
      </div>

      <p className="mt-4 line-clamp-2 text-sm text-muted-foreground">{job.shortDescription}</p>

      <JobMeta job={job} className="mt-5" />

      <div className="mt-4 flex flex-wrap gap-2">
        {job.skills.slice(0, 5).map((s) => (
          <span
            key={s}
            className="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground"
          >
            {s}
          </span>
        ))}
      </div>
    </article>
  );
}
