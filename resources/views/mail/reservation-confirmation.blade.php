<x-mail::message>
# We hebben je aanvraag goed ontvangen

## Hallo {{ $tenantInformation->firstName }} {{ $tenantInformation->lastName }},

We hebben je aanvraag voor een huring van onze domeinen en of lokalen goed ontvangen.
We behandelen deze zo snel mogelijk en contacteren je bij een bevestiging of verdere vragen.

---

Moest je nog vragen of opmerkingen hebben kan je gerust contact met ons opnemen, doormiddel van het contact formulier op onze website.

Met vriendelijke groet,<br>
{{ config('app.name') }}
</x-mail::message>
