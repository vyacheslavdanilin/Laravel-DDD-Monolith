# Laravel DDD Monolith

## О проекте

**Laravel DDD Monolith** – монолитное приложение на Laravel с использованием подхода Domain-Driven Design (DDD). В проекте два контекста (Bounded Contexts) — LUVR и ShiftPlanning.

## Запуск проекта

```bash
make run
```


## API-эндпоинты

### 1. Создание смены (Shift)

```http
POST http://127.0.0.1:8000/api/v1/shifts
```

#### **Тело запроса:**

```json
{
    "start_date_time": "2025-02-12T08:00:00Z",
    "end_date_time": "2025-02-12T16:00:00Z"
}
```

#### **Ответ:**

```json
{
    "id": 1,
    "start_date_time": "2025-02-12T08:00:00Z",
    "end_date_time": "2025-02-12T16:00:00Z",
    "status": "Поиск исполнителя"
}
```

## TODO:

- Добавить больше доменов
- GraphQL
- CQRS
- Тесты