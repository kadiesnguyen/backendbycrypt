import { BillListContainer } from "@/features/bills/BillListContainer";

export default function FundHistoryPage() {
  return (
    <BillListContainer
      titleKey="page.fundHistory.title"
      descriptionKey="page.fundHistory.description"
      noResultsKey="page.fundHistory.noResults"
    />
  );
}
