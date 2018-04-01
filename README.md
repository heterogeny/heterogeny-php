#### Experimental

Heterogenize PHP's data structures, like `array` that is split into `Seq`, `Dict` and `Tuple`.

- `Seq` is a no-key `array`, setting keys that is not an integer will result in an `Exception`, every `Seq` will be JSON encoded as `[]`;

- `Dict` is a keyed `array`, whether key is string or not, every `Dict` will be JSON encoded as `{}`;

- `Tuple` is just like a `Seq`, maybe in the future helpers will be added to `Tuple`.

Also supplying some helpers for JSON encoding/decoding without headaches on checking whether 
something is `array` or `array` with keys.

__Performance is not guaranteed at this point.__