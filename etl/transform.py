import pandas as pd


def transform_expenses(expenses):
    df = pd.DataFrame(expenses)

    if df.empty:
        return df

    df["expense_date"] = pd.to_datetime(df["expense_date"])
    df["amount"] = pd.to_numeric(df["amount"], errors="coerce")

    df["year"] = df["expense_date"].dt.year
    df["month"] = df["expense_date"].dt.month
    df["month_name"] = df["expense_date"].dt.month_name()
    df["day"] = df["expense_date"].dt.day
    df["quarter"] = df["expense_date"].dt.quarter

    df["category_name"] = df["category"].apply(
        lambda category: category["name"] if isinstance(category, dict) else None
    )

    df["period"] = (
        df["year"].astype(str)
        + "-"
        + df["month"].astype(str).str.zfill(2)
    )

    df = df[
        [
            "id",
            "description",
            "amount",
            "expense_date",
            "year",
            "month",
            "month_name",
            "quarter",
            "day",
            "period",
            "category_id",
            "category_name",
        ]
    ]

    return df

def transform_categories(categories):
    df = pd.DataFrame(categories)

    if df.empty:
        return df

    return df[
        [
            "id",
            "name",
            "created_at",
            "updated_at",
        ]
    ]


def transform_budgets(budgets):
    df = pd.DataFrame(budgets)

    if df.empty:
        return df

    df["amount"] = pd.to_numeric(df["amount"], errors="coerce")

    df["period"] = (
        df["year"].astype(str)
        + "-"
        + df["month"].astype(str).str.zfill(2)
    )

    df["category_name"] = df["category"].apply(
        lambda category: category["name"] if isinstance(category, dict) else None
    )

    return df[
        [
            "id",
            "category_id",
            "category_name",
            "month",
            "year",
            "period",
            "amount",
        ]
    ]

def create_dim_calendar(df_expenses, df_budgets):
    dates = []

    if not df_expenses.empty:
        dates.extend(df_expenses["expense_date"].tolist())

    if not df_budgets.empty:
        budget_dates = pd.to_datetime(
            df_budgets["period"] + "-01",
            errors="coerce"
        )
        dates.extend(budget_dates.tolist())

    if not dates:
        return pd.DataFrame()

    start_date = min(dates)
    end_date = max(dates)

    calendar = pd.DataFrame({
        "date": pd.date_range(start=start_date, end=end_date, freq="D")
    })

    calendar["year"] = calendar["date"].dt.year
    calendar["month"] = calendar["date"].dt.month
    calendar["month_name"] = calendar["date"].dt.month_name()
    calendar["quarter"] = calendar["date"].dt.quarter
    calendar["day"] = calendar["date"].dt.day
    calendar["weekday"] = calendar["date"].dt.weekday + 1
    calendar["weekday_name"] = calendar["date"].dt.day_name()
    calendar["is_weekend"] = calendar["weekday"].isin([6, 7])

    calendar["period"] = (
        calendar["year"].astype(str)
        + "-"
        + calendar["month"].astype(str).str.zfill(2)
    )

    return calendar

def create_budget_vs_expenses(df_expenses, df_budgets):
    if df_budgets.empty:
        return pd.DataFrame()

    expenses_grouped = (
        df_expenses
        .groupby(["category_id", "period"], as_index=False)
        .agg(spent_amount=("amount", "sum"))
    )

    budgets = df_budgets.rename(columns={
        "amount": "budget_amount"
    })

    result = budgets.merge(
        expenses_grouped,
        on=["category_id", "period"],
        how="left"
    )

    result["spent_amount"] = result["spent_amount"].fillna(0)

    result["available_amount"] = (
        result["budget_amount"] - result["spent_amount"]
    )

    result["usage_percentage"] = (
        result["spent_amount"] / result["budget_amount"] * 100
    ).round(2)

    result["status"] = result["usage_percentage"].apply(
        lambda value: "Sobrepresupuesto" if value > 100 else "Dentro del presupuesto"
    )

    return result[
        [
            "category_id",
            "category_name",
            "period",
            "budget_amount",
            "spent_amount",
            "available_amount",
            "usage_percentage",
            "status",
        ]
    ]
