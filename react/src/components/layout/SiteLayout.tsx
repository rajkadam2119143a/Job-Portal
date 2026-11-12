import { Link } from "@tanstack/react-router";
import { Menu, Briefcase } from "lucide-react";
import { useState, type ReactNode } from "react";
import { Button } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";

const nav = [
  { to: "/", label: "Home" },
  { to: "/jobs", label: "Jobs" },
  { to: "/departments", label: "Departments" },
  { to: "/locations", label: "Locations" },
  { to: "/about", label: "About" },
  { to: "/contact", label: "Contact" },
] as const;

function NavLinks({ onClick }: { onClick?: () => void }) {
  return (
    <>
      {nav.map((n) => (
        <Link
          key={n.to}
          to={n.to}
          onClick={onClick}
          activeOptions={{ exact: n.to === "/" }}
          activeProps={{ className: "text-brand" }}
          inactiveProps={{ className: "text-muted-foreground" }}
          className="text-sm font-medium transition-colors hover:text-brand"
        >
          {n.label}
        </Link>
      ))}
    </>
  );
}

export function SiteHeader() {
  const [open, setOpen] = useState(false);
  return (
    <header className="sticky top-0 z-40 border-b border-border bg-surface/90 backdrop-blur">
      <div className="container-page flex h-16 items-center justify-between gap-6">
        <Link to="/" className="flex items-center gap-2">
          <span className="flex size-8 items-center justify-center rounded-md bg-brand text-brand-foreground">
            <Briefcase className="size-4" />
          </span>
          <span className="font-display text-base font-bold tracking-tight">
            Northwind Careers
          </span>
        </Link>

        <nav className="hidden items-center gap-7 lg:flex">
          <NavLinks />
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <Button asChild variant="ghost" size="sm">
            <Link to="/careers-admin">Admin</Link>
          </Button>
          <Button asChild size="sm">
            <Link to="/jobs">Browse openings</Link>
          </Button>
        </div>

        <Sheet open={open} onOpenChange={setOpen}>
          <SheetTrigger asChild className="lg:hidden">
            <Button variant="outline" size="icon" aria-label="Open menu">
              <Menu className="size-4" />
            </Button>
          </SheetTrigger>
          <SheetContent side="right" className="w-72">
            <div className="mt-8 flex flex-col gap-5 px-4">
              <NavLinks onClick={() => setOpen(false)} />
              <Link
                to="/careers-admin"
                onClick={() => setOpen(false)}
                className="text-sm font-medium text-muted-foreground"
              >
                Admin
              </Link>
              <Button asChild onClick={() => setOpen(false)}>
                <Link to="/jobs">Browse openings</Link>
              </Button>
            </div>
          </SheetContent>
        </Sheet>
      </div>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="mt-20 border-t border-border bg-surface-muted">
      <div className="container-page grid gap-10 py-14 md:grid-cols-4">
        <div>
          <div className="font-display text-base font-bold">Northwind Careers</div>
          <p className="mt-3 max-w-xs text-sm text-muted-foreground">
            We build dependable digital products for millions of people across India.
            Come and build them with us.
          </p>
        </div>
        <div>
          <h4 className="text-sm font-semibold">Explore</h4>
          <ul className="mt-4 space-y-2 text-sm text-muted-foreground">
            <li><Link to="/jobs" className="hover:text-brand">All jobs</Link></li>
            <li><Link to="/categories" className="hover:text-brand">Categories</Link></li>
            <li><Link to="/departments" className="hover:text-brand">Departments</Link></li>
            <li><Link to="/locations" className="hover:text-brand">Locations</Link></li>
          </ul>
        </div>
        <div>
          <h4 className="text-sm font-semibold">Company</h4>
          <ul className="mt-4 space-y-2 text-sm text-muted-foreground">
            <li><Link to="/about" className="hover:text-brand">About us</Link></li>
            <li><Link to="/contact" className="hover:text-brand">Contact</Link></li>
            <li><Link to="/careers-admin" className="hover:text-brand">Recruiter admin</Link></li>
          </ul>
        </div>
        <div>
          <h4 className="text-sm font-semibold">Get in touch</h4>
          <ul className="mt-4 space-y-2 text-sm text-muted-foreground">
            <li>people@company.example</li>
            <li>+91 22 4000 1200</li>
            <li>Nariman Point, Mumbai</li>
          </ul>
        </div>
      </div>
      <div className="border-t border-border">
        <div className="container-page flex flex-col gap-2 py-5 text-xs text-muted-foreground md:flex-row md:items-center md:justify-between">
          <span>© 2026 Northwind Digital. All rights reserved.</span>
          <span>Equal opportunity employer</span>
        </div>
      </div>
    </footer>
  );
}

export function SiteLayout({ children }: { children: ReactNode }) {
  return (
    <div className="flex min-h-screen flex-col bg-background">
      <SiteHeader />
      <main className="flex-1">{children}</main>
      <SiteFooter />
    </div>
  );
}
