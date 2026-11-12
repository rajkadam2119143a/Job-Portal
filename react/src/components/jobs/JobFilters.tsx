import { SlidersHorizontal, X } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import { useState } from "react";
import type { JobFilters as Filters } from "@/types";
import {
  jobTypes,
  workModes,
  experienceLevels,
  datePostedOptions,
} from "@/data/taxonomies";
import { getCategories, getDepartments, getLocations } from "@/services/taxonomyService";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet";
import { Separator } from "@/components/ui/separator";
import { Badge } from "@/components/ui/badge";

export type FilterPatch = Partial<Filters>;

function Group({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="space-y-3">
      <h4 className="text-sm font-semibold">{title}</h4>
      {children}
    </div>
  );
}

function CheckList({
  options,
  value,
  onChange,
  name,
}: {
  options: { label: string; value: string; count?: number }[];
  value?: string;
  onChange: (v: string | undefined) => void;
  name: string;
}) {
  return (
    <div className="space-y-2">
      {options.map((o) => {
        const id = `${name}-${o.value}`;
        const checked = value === o.value;
        return (
          <div key={o.value} className="flex items-center gap-2">
            <Checkbox
              id={id}
              checked={checked}
              onCheckedChange={(c) => onChange(c ? o.value : undefined)}
            />
            <Label htmlFor={id} className="flex-1 cursor-pointer text-sm font-normal">
              {o.label}
            </Label>
            {typeof o.count === "number" && (
              <span className="text-xs text-muted-foreground">{o.count}</span>
            )}
          </div>
        );
      })}
    </div>
  );
}

export function FiltersPanel({
  filters,
  onChange,
  onReset,
}: {
  filters: Filters;
  onChange: (patch: FilterPatch) => void;
  onReset: () => void;
}) {
  const { data: categories = [] } = useQuery({ queryKey: ["categories"], queryFn: getCategories });
  const { data: departments = [] } = useQuery({ queryKey: ["departments"], queryFn: getDepartments });
  const { data: locations = [] } = useQuery({ queryKey: ["locations"], queryFn: getLocations });

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h3 className="font-display text-base font-semibold">Filters</h3>
        <Button variant="ghost" size="sm" onClick={onReset}>
          Reset
        </Button>
      </div>

      <Group title="Keyword">
        <Input
          value={filters.keyword ?? ""}
          placeholder="Job title or skill"
          onChange={(e) => onChange({ keyword: e.target.value || undefined })}
        />
      </Group>
      <Separator />
      <Group title="Location">
        <CheckList
          name="location"
          value={filters.location}
          onChange={(v) => onChange({ location: v })}
          options={locations.map((l) => ({ label: l.name, value: l.slug, count: l.count }))}
        />
      </Group>
      <Separator />
      <Group title="Department">
        <CheckList
          name="department"
          value={filters.department}
          onChange={(v) => onChange({ department: v })}
          options={departments.map((d) => ({ label: d.name, value: d.slug, count: d.count }))}
        />
      </Group>
      <Separator />
      <Group title="Category">
        <CheckList
          name="category"
          value={filters.category}
          onChange={(v) => onChange({ category: v })}
          options={categories.map((c) => ({ label: c.name, value: c.slug, count: c.count }))}
        />
      </Group>
      <Separator />
      <Group title="Job type">
        <CheckList
          name="jobType"
          value={filters.jobType}
          onChange={(v) => onChange({ jobType: v })}
          options={jobTypes.map((t) => ({ label: t, value: t.toLowerCase().replace(/\s+/g, "-") }))}
        />
      </Group>
      <Separator />
      <Group title="Work mode">
        <CheckList
          name="workMode"
          value={filters.workMode}
          onChange={(v) => onChange({ workMode: v })}
          options={workModes.map((t) => ({ label: t, value: t.toLowerCase() }))}
        />
      </Group>
      <Separator />
      <Group title="Experience">
        <CheckList
          name="experience"
          value={filters.experience}
          onChange={(v) => onChange({ experience: v })}
          options={experienceLevels.map((t) => ({
            label: t,
            value: t.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/(^-|-$)/g, ""),
          }))}
        />
      </Group>
      <Separator />
      <Group title="Date posted">
        <RadioGroup
          value={filters.datePosted ?? "any"}
          onValueChange={(v) => onChange({ datePosted: v === "any" ? undefined : v })}
          className="space-y-2"
        >
          {datePostedOptions.map((o) => (
            <div key={o.value} className="flex items-center gap-2">
              <RadioGroupItem id={`date-${o.value}`} value={o.value} />
              <Label htmlFor={`date-${o.value}`} className="cursor-pointer text-sm font-normal">
                {o.label}
              </Label>
            </div>
          ))}
        </RadioGroup>
      </Group>
    </div>
  );
}

export function ActiveFilterChips({
  filters,
  onChange,
}: {
  filters: Filters;
  onChange: (patch: FilterPatch) => void;
}) {
  const keys: (keyof Filters)[] = [
    "keyword",
    "location",
    "department",
    "category",
    "jobType",
    "workMode",
    "experience",
    "datePosted",
  ];
  const active = keys.filter((k) => filters[k]);
  if (!active.length) return null;
  return (
    <div className="flex flex-wrap gap-2">
      {active.map((k) => (
        <Badge
          key={k}
          variant="secondary"
          className="cursor-pointer gap-1 py-1"
          onClick={() => onChange({ [k]: undefined } as FilterPatch)}
        >
          {String(filters[k]).replace(/-/g, " ")}
          <X className="size-3" />
        </Badge>
      ))}
    </div>
  );
}

export function MobileFilters(props: {
  filters: Filters;
  onChange: (patch: FilterPatch) => void;
  onReset: () => void;
}) {
  const [open, setOpen] = useState(false);
  return (
    <Sheet open={open} onOpenChange={setOpen}>
      <SheetTrigger asChild>
        <Button variant="outline" className="lg:hidden">
          <SlidersHorizontal className="size-4" /> Filters
        </Button>
      </SheetTrigger>
      <SheetContent side="left" className="w-[88vw] max-w-sm overflow-y-auto">
        <SheetHeader>
          <SheetTitle>Filter jobs</SheetTitle>
        </SheetHeader>
        <div className="px-4 pb-10">
          <FiltersPanel {...props} />
          <Button className="mt-6 w-full" onClick={() => setOpen(false)}>
            Show results
          </Button>
        </div>
      </SheetContent>
    </Sheet>
  );
}
