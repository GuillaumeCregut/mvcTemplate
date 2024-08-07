<?php

namespace Editiel98\Kernel\WebInterface;

class RestResponseHandler extends ResponseHandler
{
    /**
     * @param  mixed[] $data
     *
     * @return self
     */
    public function prepareJson(array $data): self
    {
        $this->addHeader('content-type', 'application/json');
        try {
            $content = json_encode($data);
            if ($content) {
                $this->setContent($content);
            } else {
                throw new \Exception('Error in JSON encoding');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error in JSON encoding');
        }
        return $this;
    }
}
