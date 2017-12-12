<p align="center">
    <h1 align="center">EasyWeChat Composer Plugin</h1>
</p>


This is the composer plugin for [EasyWeChat](https://github.com/overtrue/wechat).

[![Latest Stable Version](https://poser.pugx.org/easywechat-composer/easywechat-composer/v/stable.png)](https://packagist.org/packages/easywechat-composer/easywechat-composer)


Usage
---

Set the `type` to be `easywechat-extension` in your package composer.json file:

```json
{
    "name": "your/package",
    "type": "easywechat-extension"
}
```

Specify server observer classes in the extra section:

```json
{
    "name": "your/package",
    "type": "easywechat-extension",
    "extra": {
        "observers": [
            "Acme\\Observers\\Handler"
        ]
    }
}
```

Examples
---
* [easywechat-composer/open-platform-testcase](https://github.com/mingyoung/open-platform-testcase)
