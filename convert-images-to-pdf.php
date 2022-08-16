<?php

/**
 * @author Kev
 * @param string $directory The directory where the files are localized (supports .png, .PNG, .jpg, .JPG, .jpeg, .JPEG extensions).
 * @return array Image files array or empty array.
 */
function getImageFilesFromDirectory(string $directory): array
{
    $pattern = "{$directory}*.{[pP][nN][gG],[jJ][pP][eE][gG],[jJ][pP][gG]}";

    $files = glob(
        pattern: $pattern,
        flags: GLOB_BRACE
    );

    if ($files === false) {
        throw new LogicException(
            message: 'glob() failed'
        );
    }

    return $files;
}

/**
 * @author Kev
 * @param array $files The array of image files to convert in one only PDF file.
 * @param string $pdfFilename The pdf file name for output.
 * @throws ImagickException
 */
function convertImagesToPDF(
    array $files,
    string $pdfFilename
): void
{
    /*
        Note: On Ubuntu, if you have this error: PHP Fatal error:  Uncaught ImagickException: attempt to perform an operation not allowed by the security policy `PDF' @ error/constitute.c/IsCoderAuthorized/421
        Comment the line below in /etc/ImageMagick-6/policy.xml:
        <!-- <policy domain="coder" rights="none" pattern="PDF" /> -->
     */
    $pdf = new Imagick(
        files: $files
    );

    $pdf->setImageFormat(
        format: 'pdf'
    );

    $booleanResult = $pdf->writeImages(
        filename: $pdfFilename,
        adjoin: true
    );

    if ($booleanResult !== true) {
        throw new LogicException(
            message: 'writeImages() failed'
        );
    }
}


