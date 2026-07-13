import {
  array,
  int,
  object,
  regex,
  string,
  unknown as unknownSchema,
} from "zod/mini";
import type { infer as Infer } from "zod/mini";

const RECENT_CLIPS_KEY = "recentClips";
const RECENT_CLIP_TTL_MS = 2 * 24 * 60 * 60 * 1000;
const MAX_RECENT_CLIPS = 6;

const clipCodeSchema = string().check(regex(/^[A-Za-z0-9]{5}$/));
const recentClipSchema = object({
  code: clipCodeSchema,
  expiresAt: int(),
});
const storedRecentClipsSchema = array(unknownSchema());

export type RecentClip = Infer<typeof recentClipSchema>;

export const isClipCode = (value: unknown): value is string =>
  clipCodeSchema.safeParse(value).success;

const removeStoredRecentClips = () => {
  try {
    sessionStorage.removeItem(RECENT_CLIPS_KEY);
  } catch {
    // History is optional when session storage is unavailable.
  }
};

export const getRecentlyMadeClipEntries = (): RecentClip[] => {
  try {
    localStorage.removeItem(RECENT_CLIPS_KEY);
  } catch {
    // Storage can be unavailable in privacy-restricted browser contexts.
  }

  try {
    const initialValue = sessionStorage.getItem(RECENT_CLIPS_KEY);
    if (!initialValue) return [];

    const parsedValue = storedRecentClipsSchema.safeParse(
      JSON.parse(initialValue),
    );
    if (!parsedValue.success) {
      removeStoredRecentClips();
      return [];
    }

    const now = Date.now();
    const seenCodes = new Set<string>();
    const recentClips = parsedValue.data
      .flatMap((entry) => {
        const parsedEntry = recentClipSchema.safeParse(entry);
        return parsedEntry.success ? [parsedEntry.data] : [];
      })
      .filter(
        (entry) =>
          entry.expiresAt > now && entry.expiresAt <= now + RECENT_CLIP_TTL_MS,
      )
      .filter((entry) => {
        if (seenCodes.has(entry.code)) return false;
        seenCodes.add(entry.code);
        return true;
      })
      .slice(0, MAX_RECENT_CLIPS);

    try {
      if (recentClips.length > 0) {
        sessionStorage.setItem(RECENT_CLIPS_KEY, JSON.stringify(recentClips));
      } else {
        sessionStorage.removeItem(RECENT_CLIPS_KEY);
      }
    } catch {
      // History is optional when session storage is unavailable.
    }

    return recentClips;
  } catch {
    removeStoredRecentClips();
    return [];
  }
};

export const rememberClip = (clipCode: string) => {
  if (!isClipCode(clipCode)) return;

  const recentClips = getRecentlyMadeClipEntries().filter(
    (entry) => entry.code !== clipCode,
  );
  recentClips.unshift({
    code: clipCode,
    expiresAt: Date.now() + RECENT_CLIP_TTL_MS,
  });

  try {
    sessionStorage.setItem(
      RECENT_CLIPS_KEY,
      JSON.stringify(recentClips.slice(0, MAX_RECENT_CLIPS)),
    );
  } catch {
    // History is optional when session storage is unavailable.
  }
};
