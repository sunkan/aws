<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\ValueObject\AccessControlPolicy;
use AsyncAws\S3\ValueObject\Grantee;
use AsyncAws\S3\ValueObject\Owner;

final class PutObjectAclRequest extends Input
{
    /**
     * The canned ACL to apply to the object. For more information, see Canned ACL.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#CannedACL
     *
     * @var null|ObjectCannedACL::*
     */
    private $ACL;

    /**
     * Contains the elements that set the ACL permissions for an object per grantee.
     *
     * @var AccessControlPolicy|null
     */
    private $AccessControlPolicy;

    /**
     * The bucket name that contains the object to which you want to attach the ACL.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * The base64-encoded 128-bit MD5 digest of the data. This header must be used as a message integrity check to verify
     * that the request body was not corrupted in transit. For more information, go to RFC 1864.&gt;.
     *
     * @see http://www.ietf.org/rfc/rfc1864.txt
     *
     * @var string|null
     */
    private $ContentMD5;

    /**
     * Allows grantee the read, write, read ACP, and write ACP permissions on the bucket.
     *
     * @var string|null
     */
    private $GrantFullControl;

    /**
     * Allows grantee to list the objects in the bucket.
     *
     * @var string|null
     */
    private $GrantRead;

    /**
     * Allows grantee to read the bucket ACL.
     *
     * @var string|null
     */
    private $GrantReadACP;

    /**
     * Allows grantee to create, overwrite, and delete any object in the bucket.
     *
     * @var string|null
     */
    private $GrantWrite;

    /**
     * Allows grantee to write the ACL for the applicable bucket.
     *
     * @var string|null
     */
    private $GrantWriteACP;

    /**
     * Key for which the PUT operation was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * @var null|RequestPayer::*
     */
    private $RequestPayer;

    /**
     * VersionId used to reference a specific version of the object.
     *
     * @var string|null
     */
    private $VersionId;

    /**
     * The account id of the expected bucket owner. If the bucket is owned by a different account, the request will fail
     * with an HTTP `403 (Access Denied)` error.
     *
     * @var string|null
     */
    private $ExpectedBucketOwner;

    /**
     * @param array{
     *   ACL?: ObjectCannedACL::*,
     *   AccessControlPolicy?: AccessControlPolicy|array,
     *   Bucket?: string,
     *   ContentMD5?: string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWrite?: string,
     *   GrantWriteACP?: string,
     *   Key?: string,
     *   RequestPayer?: RequestPayer::*,
     *   VersionId?: string,
     *   ExpectedBucketOwner?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ACL = $input['ACL'] ?? null;
        $this->AccessControlPolicy = isset($input['AccessControlPolicy']) ? AccessControlPolicy::create($input['AccessControlPolicy']) : null;
        $this->Bucket = $input['Bucket'] ?? null;
        $this->ContentMD5 = $input['ContentMD5'] ?? null;
        $this->GrantFullControl = $input['GrantFullControl'] ?? null;
        $this->GrantRead = $input['GrantRead'] ?? null;
        $this->GrantReadACP = $input['GrantReadACP'] ?? null;
        $this->GrantWrite = $input['GrantWrite'] ?? null;
        $this->GrantWriteACP = $input['GrantWriteACP'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
        $this->ExpectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ObjectCannedACL::*|null
     */
    public function getACL(): ?string
    {
        return $this->ACL;
    }

    public function getAccessControlPolicy(): ?AccessControlPolicy
    {
        return $this->AccessControlPolicy;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    /**
     * @deprecated
     */
    public function getContentMD5(): ?string
    {
        @trigger_error(\sprintf('The property "ContentMD5" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->ContentMD5;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->ExpectedBucketOwner;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->GrantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->GrantRead;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->GrantReadACP;
    }

    public function getGrantWrite(): ?string
    {
        return $this->GrantWrite;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->GrantWriteACP;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->ACL) {
            if (!ObjectCannedACL::exists($this->ACL)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ACL" for "%s". The value "%s" is not a valid "ObjectCannedACL".', __CLASS__, $this->ACL));
            }
            $headers['x-amz-acl'] = $this->ACL;
        }
        if (null !== $this->ContentMD5) {
            $headers['Content-MD5'] = $this->ContentMD5;
        }
        if (null !== $this->GrantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->GrantFullControl;
        }
        if (null !== $this->GrantRead) {
            $headers['x-amz-grant-read'] = $this->GrantRead;
        }
        if (null !== $this->GrantReadACP) {
            $headers['x-amz-grant-read-acp'] = $this->GrantReadACP;
        }
        if (null !== $this->GrantWrite) {
            $headers['x-amz-grant-write'] = $this->GrantWrite;
        }
        if (null !== $this->GrantWriteACP) {
            $headers['x-amz-grant-write-acp'] = $this->GrantWriteACP;
        }
        if (null !== $this->RequestPayer) {
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }
        if (null !== $this->ExpectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->ExpectedBucketOwner;
        }

        // Prepare query
        $query = [];
        if (null !== $this->VersionId) {
            $query['versionId'] = $this->VersionId;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key'])) . '?acl';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $this->requestBody($document, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ObjectCannedACL::*|null $value
     */
    public function setACL(?string $value): self
    {
        $this->ACL = $value;

        return $this;
    }

    public function setAccessControlPolicy(?AccessControlPolicy $value): self
    {
        $this->AccessControlPolicy = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    /**
     * @deprecated
     */
    public function setContentMD5(?string $value): self
    {
        @trigger_error(\sprintf('The property "ContentMD5" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->ContentMD5 = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->ExpectedBucketOwner = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->GrantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->GrantRead = $value;

        return $this;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->GrantReadACP = $value;

        return $this;
    }

    public function setGrantWrite(?string $value): self
    {
        $this->GrantWrite = $value;

        return $this;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->GrantWriteACP = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }

    private function requestBody(\DomNode $node, \DomDocument $document): void
    {
        if (null !== $v = $this->AccessControlPolicy) {
            $node->appendChild($child = $document->createElement('AccessControlPolicy'));
            $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
            $v->requestBody($child, $document);
        }
    }
}
