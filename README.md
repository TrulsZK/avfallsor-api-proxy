# Avfall Sør API Proxy

Unofficial API Proxy script to get next Avfall Sør garbage bin collection as JSON.

This API can be hosted on a webserver and allow you to use the data from Avfall Sør in other scripts/services, e.g. Home Assistant RESTful sensor.

## Obtain the `addressid` from Avfall Sør official website

1. Go to https://avfallsor.no/.
2. Under "Finn hentedag" enter your address and make a search for your address.
3. In the address bar of the browser you will get a unique ID for your address. e.g. https://avfallsor.no/henting-av-avfall/finn-hentedag/d5b927ae-e88d-49d2-b040-7f30906b3a16/ where `d5b927ae-e88d-49d2-b040-7f30906b3a16` is the `addressid` to use in the script.

## Usage

To use the script send a GET request to the php script with `addressid` as payload. You will then get a JSON back with the following response: (E.g. https://127.0.0.1/avfallsor.php?addressid=d5b927ae-e88d-49d2-b040-7f30906b3a16)

- `rest` Next collection date for Restavfall in ISO 8601 format (e.g. 2023-01-01)
- `bio` Next collection date for Bioavfall in ISO 8601 format (e.g. 2023-01-01)
- `papp` Next collection date for Papp og papir in ISO 8601 format (e.g. 2023-01-01)
- `plast` Next collection date for Plastemballasje in ISO 8601 format (e.g. 2023-01-01)
- `glass` Next collection date for Glass- og metallemballasje in ISO 8601 format (e.g. 2023-01-01)
- `rest_formatted` Next collection date for Restavfall in `D j M Y` format (e.g. 1 Jan 2023)
- `bio_formatted` Next collection date for Bioavfall in `D j M Y` format (e.g. 1 Jan 2023)
- `papp_formatted` Next collection date for Papp og papir in `D j M Y` format (e.g. 1 Jan 2023)
- `plast_formatted` Next collection date for Plastemballasje in `D j M Y` format (e.g. 1 Jan 2023)
- `glass_formatted` Next collection date for Glass- og metallemballasje in `D j M Y` format (e.g. 1 Jan 2023)
- `rest_todayortomorrow` `1` If Restavfall will be collected today or tommorow (otherwise `0`)
- `bio_todayortomorrow` `1` If Bioavfall will be collected today or tommorow (otherwise `0`)
- `papp_todayortomorrow` `1` If Papp og papir will be collected today or tommorow (otherwise `0`)
- `plast_todayortomorrow` `1` If Plastemballasje will be collected today or tommorow (otherwise `0`)
- `glass_todayortomorrow` `1` If Glass- og metallemballasje will be collected today or tommorow (otherwise `0`)
- `rest_today` `1` If Restavfall will be collected today (otherwise `0`)
- `bio_today` `1` If Bioavfall will be collected today (otherwise `0`)
- `papp_today` `1` If Papp og papir will be collected today (otherwise `0`)
- `plast_today` `1` If Plastemballasje will be collected today (otherwise `0`)
- `glass_today` `1` If Glass- og metallemballasje will be collected today (otherwise `0`)
- `rest_tomorrow` `1` If Restavfall will be collected tomorrow (otherwise `0`)
- `bio_tomorrow` `1` If Bioavfall will be collected tomorrow (otherwise `0`)
- `papp_tomorrow` `1` If Papp og papir will be collected tomorrow (otherwise `0`)
- `plast_tomorrow` `1` If Plastemballasje will be collected tomorrow (otherwise `0`)
- `glass_tomorrow` `1` If Glass- og metallemballasje will be collected tomorrow (otherwise `0`)

Example response:

```JSON
{
  "rest": "2023-05-15",
  "bio": "2023-05-15",
  "papp": "2023-06-05",
  "plast": "2023-06-05",
  "glass": "2023-06-12",
  "rest_formatted": "Mon 15 May 2023",
  "bio_formatted": "Mon 15 May 2023",
  "papp_formatted": "Mon 5 Jun 2023",
  "plast_formatted": "Mon 5 Jun 2023",
  "glass_formatted": "Mon 12 Jun 2023",
  "rest_todayortomorrow": 1,
  "bio_todayortomorrow": 1,
  "papp_todayortomorrow": 0,
  "plast_todayortomorrow": 0,
  "glass_todayortomorrow": 0,
  "rest_today": 0,
  "bio_today": 0,
  "papp_today": 0,
  "plast_today": 0,
  "glass_today": 0,
  "rest_tomorrow": 1,
  "bio_tomorrow": 1,
  "papp_tomorrow": 0,
  "plast_tomorrow": 0,
  "glass_tomorrow": 0
}
```
