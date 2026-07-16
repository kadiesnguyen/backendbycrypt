import { Suspense } from "react";
import { NewsListContainer } from "@/features/content/news/NewsListContainer";
import { NewsListSkeleton } from "@/features/content/news/NewsListSkeleton";

export default function NewsPage() {
  return (
    <Suspense fallback={<NewsListSkeleton />}>
      <NewsListContainer />
    </Suspense>
  );
}
