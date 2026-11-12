import { createFileRoute, Link, useNavigate } from "@tanstack/react-router";
import { useQuery } from "@tanstack/react-query";
import { useState } from "react";
import { ArrowRight, Search, MapPin, Sparkles, Users, Globe2, HeartHandshake } from "lucide-react";
import { SiteLayout } from "@/components/layout/SiteLayout";
import { JobCard } from "@/components/jobs/JobCard";
import { JobListSkeleton, ErrorState } from "@/components/common/States";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { getFeaturedJobs, getLatestJobs, getJobStats } from "@/services/jobsService";
import { getCategories, getDepartments, getLocations } from "@/services/taxonomyService";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "Careers at Northwind Digital — Open Roles Across India" },
      {
        name: "description",
        content:
          "Explore engineering, product, design and commercial openings at Northwind Digital in Mumbai, Pune, Bengaluru, Delhi, Hyderabad and remote.",
      },
      { property: "og:title", content: "Careers at Northwind Digital" },
      {
        property: "og:description",
        content: "Browse open roles and apply in minutes. Hybrid, remote and onsite positions.",
      },
    ],
  }),
  component: Home,
});

function Home() {
  const navigate = useNavigate();
  const [keyword, setKeyword] = useState("");
  const [location, setLocation] = useState("");

  const featured = useQuery({ queryKey: ["jobs", "featured"], queryFn: () => getFeaturedJobs(4) });
  const latest = useQuery({ queryKey: ["jobs", "latest"], queryFn: () => getLatestJobs(6) });
  const stats = useQuery({ queryKey: ["jobs", "stats"], queryFn: getJobStats });
  const categories = useQuery({ queryKey: ["categories"], queryFn: getCategories });
  const departments = useQuery({ queryKey: ["departments"], queryFn: getDepartments });
  const locations = useQuery({ queryKey: ["locations"], queryFn: getLocations });

  const search = () =>
    navigate({
      to: "/jobs",
      search: {
        keyword: keyword || undefined,
        location: location || undefined,
        page: 1,
      },
    });

  return (
    <SiteLayout>
      <section className="relative overflow-hidden border-b border-border bg-surface-muted">
        <div className="container-page grid gap-12 py-16 md:py-24 lg:grid-cols-[1.1fr_0.9fr]">
          <div>
            <span className="inline-flex items-center gap-2 rounded-full bg-brand-soft px-3 py-1 text-xs font-semibold text-accent-foreground">
              <Sparkles className="size-3.5" /> {stats.data?.open ?? "—"} roles open right now
            </span>
            <h1 className="mt-5 text-4xl font-bold leading-[1.08] md:text-6xl">
              Build work that reaches
              <span className="text-brand"> millions of people</span>
            </h1>
            <p className="mt-5 max-w-xl text-base text-muted-foreground md:text-lg">
              We are a product company of engineers, designers, analysts and operators. Find a role
              that fits how you want to work — remote, hybrid or in one of our six offices.
            </p>

            <div className="mt-8 rounded-xl border border-border bg-surface p-3 shadow-card">
              <div className="flex flex-col gap-3 md:flex-row">
                <div className="relative flex-1">
                  <Search className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                  <Input
                    className="h-11 pl-9"
                    placeholder="Job title, skill or keyword"
                    value={keyword}
                    onChange={(e) => setKeyword(e.target.value)}
                    onKeyDown={(e) => e.key === "Enter" && search()}
                  />
                </div>
                <div className="relative flex-1">
                  <MapPin className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                  <Input
                    className="h-11 pl-9"
                    placeholder="Location"
                    value={location}
                    onChange={(e) => setLocation(e.target.value)}
                    onKeyDown={(e) => e.key === "Enter" && search()}
                  />
                </div>
                <Button className="h-11 px-6" onClick={search}>
                  Search jobs
                </Button>
              </div>
            </div>

            <div className="mt-6 flex flex-wrap gap-2 text-sm text-muted-foreground">
              <span>Popular:</span>
              {["engineer", "designer", "analyst", "manager"].map((t) => (
                <Link
                  key={t}
                  to="/jobs"
                  search={{ keyword: t, page: 1 }}
                  className="rounded-full border border-border px-3 py-0.5 text-xs hover:border-brand hover:text-brand"
                >
                  {t}
                </Link>
              ))}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4 self-center">
            {[
              { icon: Users, k: stats.data?.total ?? 0, l: "Roles published" },
              { icon: Globe2, k: 6, l: "Office locations" },
              { icon: HeartHandshake, k: 11, l: "Departments hiring" },
              { icon: Sparkles, k: stats.data?.open ?? 0, l: "Currently open" },
            ].map((s) => (
              <div key={s.l} className="rounded-xl border border-border bg-surface p-6 shadow-card">
                <s.icon className="size-5 text-brand" />
                <div className="mt-4 font-display text-3xl font-bold">{s.k}</div>
                <div className="mt-1 text-sm text-muted-foreground">{s.l}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="container-page py-16">
        <div className="flex items-end justify-between gap-4">
          <div>
            <h2 className="text-2xl font-bold md:text-3xl">Featured openings</h2>
            <p className="mt-2 text-sm text-muted-foreground">
              Roles our hiring managers are prioritising this month.
            </p>
          </div>
          <Button asChild variant="outline">
            <Link to="/jobs">
              All jobs <ArrowRight className="size-4" />
            </Link>
          </Button>
        </div>
        <div className="mt-8 grid gap-4 lg:grid-cols-2">
          {featured.isPending && <JobListSkeleton count={4} />}
          {featured.isError && <ErrorState onRetry={() => featured.refetch()} />}
          {featured.data?.map((j) => <JobCard key={j.id} job={j} />)}
        </div>
      </section>

      <section className="border-y border-border bg-surface-muted py-16">
        <div className="container-page">
          <h2 className="text-2xl font-bold md:text-3xl">Explore by category</h2>
          <div className="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {categories.data?.map((c) => (
              <Link
                key={c.id}
                to="/jobs"
                search={{ category: c.slug, page: 1 }}
                className="rounded-xl border border-border bg-surface p-5 shadow-card transition-all hover:-translate-y-0.5 hover:shadow-lift"
              >
                <div className="flex items-center justify-between">
                  <h3 className="font-display text-base font-semibold">{c.name}</h3>
                  <span className="rounded-full bg-brand-soft px-2 py-0.5 text-xs font-semibold text-accent-foreground">
                    {c.count}
                  </span>
                </div>
                <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">{c.description}</p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="container-page py-16">
        <div className="grid gap-10 lg:grid-cols-[1.4fr_0.6fr]">
          <div>
            <div className="flex items-end justify-between gap-4">
              <h2 className="text-2xl font-bold md:text-3xl">Latest jobs</h2>
              <Button asChild variant="ghost">
                <Link to="/jobs">View all</Link>
              </Button>
            </div>
            <div className="mt-8 space-y-4">
              {latest.isPending && <JobListSkeleton count={3} />}
              {latest.data?.map((j) => <JobCard key={j.id} job={j} />)}
            </div>
          </div>
          <aside className="space-y-8">
            <div className="rounded-xl border border-border bg-surface p-6 shadow-card">
              <h3 className="font-display text-base font-semibold">Departments</h3>
              <ul className="mt-4 space-y-2">
                {departments.data?.slice(0, 8).map((d) => (
                  <li key={d.id}>
                    <Link
                      to="/jobs"
                      search={{ department: d.slug, page: 1 }}
                      className="flex items-center justify-between text-sm text-muted-foreground hover:text-brand"
                    >
                      {d.name} <span className="text-xs">{d.count}</span>
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
            <div className="rounded-xl border border-border bg-surface p-6 shadow-card">
              <h3 className="font-display text-base font-semibold">Locations</h3>
              <ul className="mt-4 space-y-2">
                {locations.data?.map((l) => (
                  <li key={l.id}>
                    <Link
                      to="/jobs"
                      search={{ location: l.slug, page: 1 }}
                      className="flex items-center justify-between text-sm text-muted-foreground hover:text-brand"
                    >
                      {l.name} <span className="text-xs">{l.count}</span>
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          </aside>
        </div>
      </section>

      <section className="container-page pb-20">
        <div className="rounded-2xl bg-brand px-8 py-14 text-center text-brand-foreground shadow-lift">
          <h2 className="text-2xl font-bold md:text-3xl">Didn't find the right role?</h2>
          <p className="mx-auto mt-3 max-w-xl text-sm opacity-90">
            Tell us what you are great at and we will reach out when something matching opens up.
          </p>
          <Button asChild variant="secondary" className="mt-7">
            <Link to="/contact">Get in touch</Link>
          </Button>
        </div>
      </section>
    </SiteLayout>
  );
}
