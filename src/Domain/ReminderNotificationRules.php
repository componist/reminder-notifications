<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Domain;

use Illuminate\Validation\Rule;

final class ReminderNotificationRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(string $type): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'type' => ['required', Rule::in(ReminderNotificationType::allowed())],
            'time' => [
                Rule::requiredIf(fn (): bool => $type === ReminderNotificationType::DAILY),
                'nullable',
                'date_format:H:i',
            ],
            'daily' => [
                Rule::requiredIf(fn (): bool => in_array($type, [ReminderNotificationType::MONTHLY, ReminderNotificationType::YEARLY], true)),
                'nullable',
                'integer',
                'min:1',
                'max:30',
            ],
            'monthly' => [
                Rule::requiredIf(fn (): bool => $type === ReminderNotificationType::YEARLY),
                'nullable',
                'integer',
                'min:1',
                'max:12',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'title.required' => 'Der Titel ist erforderlich.',
            'email.required' => 'Die E-Mail-Adresse ist erforderlich.',
            'email.email' => 'Die E-Mail-Adresse ist ungültig.',
            'type.in' => 'Der Typ ist ungültig.',
            'time.required' => 'Die Uhrzeit ist für tägliche Erinnerungen erforderlich.',
            'time.date_format' => 'Die Uhrzeit muss im Format HH:MM vorliegen.',
            'daily.required' => 'Der Tag ist erforderlich.',
            'monthly.required' => 'Der Monat ist erforderlich.',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function scheduleAttributes(array $data): array
    {
        $type = (string) ($data['type'] ?? ReminderNotificationType::DAILY);

        return [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'email' => $data['email'],
            'type' => $type,
            'time' => $type === ReminderNotificationType::DAILY ? ($data['time'] ?? null) : null,
            'daily' => in_array($type, [ReminderNotificationType::MONTHLY, ReminderNotificationType::YEARLY], true)
                ? ($data['daily'] ?? null)
                : null,
            'monthly' => $type === ReminderNotificationType::YEARLY ? ($data['monthly'] ?? null) : null,
        ];
    }
}
