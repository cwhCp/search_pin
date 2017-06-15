{%

local json = require "cjson"
local param = _APPWEB

local data = {d=ngx.time(), type=param.type}

ngx.header["Connection"] = "closed";

ngx.say(json.encode(data))

%}
