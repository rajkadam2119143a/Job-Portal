import type { CareersSettings, Taxonomy } from "@/types";
import { db, delay } from "./store";
import { slugify } from "@/lib/format";

type Kind = "categories" | "departments" | "locations";

export const getCategories = () => delay(db.categories);
export const getDepartments = () => delay(db.departments);
export const getLocations = () => delay(db.locations);

export const getTaxonomy = (kind: Kind) => delay(db[kind]);

export async function createTaxonomy(
  kind: Kind,
  data: { name: string; description: string },
): Promise<Taxonomy> {
  const item: Taxonomy = {
    id: `${kind}-${Date.now()}`,
    slug: slugify(data.name),
    name: data.name,
    description: data.description,
    count: 0,
    status: "Active",
  };
  db[kind] = [...db[kind], item];
  return delay(item, 300);
}

export async function updateTaxonomy(
  kind: Kind,
  id: string,
  data: Partial<Taxonomy>,
): Promise<Taxonomy | null> {
  const idx = db[kind].findIndex((t) => t.id === id);
  if (idx === -1) return delay(null);
  db[kind][idx] = { ...db[kind][idx], ...data };
  return delay(db[kind][idx], 300);
}

export async function deleteTaxonomy(kind: Kind, id: string) {
  db[kind] = db[kind].filter((t) => t.id !== id);
  return delay(true, 250);
}

export const getSettings = () => delay(db.settings);

export async function updateSettings(data: Partial<CareersSettings>) {
  db.settings = { ...db.settings, ...data };
  return delay(db.settings, 400);
}
