"use client";

import { useCallback, useMemo, useState } from "react";

export function useRowSelection<T extends { id: number }>(items: T[]) {
  const [selectedIds, setSelectedIds] = useState<number[]>([]);

  const itemIds = useMemo(() => items.map((item) => item.id), [items]);

  const allSelected = items.length > 0 && selectedIds.length === items.length;
  const someSelected = selectedIds.length > 0 && !allSelected;

  const toggleAll = useCallback(() => {
    setSelectedIds((prev) => (prev.length === items.length ? [] : itemIds));
  }, [itemIds, items.length]);

  const toggleOne = useCallback((id: number) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((value) => value !== id) : [...prev, id],
    );
  }, []);

  const clearSelection = useCallback(() => {
    setSelectedIds([]);
  }, []);

  const isSelected = useCallback((id: number) => selectedIds.includes(id), [selectedIds]);

  return {
    selectedIds,
    allSelected,
    someSelected,
    toggleAll,
    toggleOne,
    clearSelection,
    isSelected,
    setSelectedIds,
  };
}
