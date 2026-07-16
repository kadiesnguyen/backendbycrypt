import { Suspense } from "react";
import { ArticleListContainer } from "@/features/content/articles/ArticleListContainer";
import { ArticleListSkeleton } from "@/features/content/articles/ArticleListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<ArticleListSkeleton />}>
      <ArticleListContainer />
    </Suspense>
  );
}
