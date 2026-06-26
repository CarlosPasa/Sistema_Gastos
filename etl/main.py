from auth import login
from extract import extract_expenses, extract_categories, extract_budgets
from transform import (
    transform_expenses,
    transform_categories,
    transform_budgets,
    create_dim_calendar,
    create_budget_vs_expenses,
)
from load import export_dataframe


def main():
    token = login()

    expenses = extract_expenses(token)
    categories = extract_categories(token)
    budgets = extract_budgets(token)

    df_expenses = transform_expenses(expenses)
    df_categories = transform_categories(categories)
    df_budgets = transform_budgets(budgets)

    df_calendar = create_dim_calendar(df_expenses, df_budgets)

    df_budget_vs_expenses = create_budget_vs_expenses(
        df_expenses,
        df_budgets
    )

    export_dataframe(df_expenses, "expenses")
    export_dataframe(df_categories, "categories")
    export_dataframe(df_budgets, "budgets")
    export_dataframe(df_calendar, "dim_calendar")
    export_dataframe(df_budget_vs_expenses, "budget_vs_expenses")

if __name__ == "__main__":
    main()
